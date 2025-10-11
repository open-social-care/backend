<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsData;
use App\Models\FormTemplate;
use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;
class AnalyticsController extends Controller
{
    public function forTemplate(FormTemplate $formTemplate, Request $request): JsonResponse
    {
        $query = AnalyticsData::where('form_template_id', $formTemplate->id);
        $period = $request->query('period');

        if ($period && $period !== 'all') {
            $startDate = $this->getStartDateFromPeriod($period);
            $query->where('created_at', '>=', $startDate);
        }

        $answers = $query->get();

        $chartData = match ($formTemplate->title) {
            'Ficha de Atendimento CCG' => $this->processCcgAnalytics($answers),
            'Plano de Acompanhamento - Albergue' => $this->processAlbergueAnalytics($answers),
            default => $this->processGenericAnalytics($answers),
        };

        return response()->json([
            'type' => 'success',
            'message' => 'Dados analíticos carregados com sucesso.',
            'data' => $chartData
        ]);
    }
    public function selectList(Organization $organization): JsonResponse
    {
        $this->authorize('viewForOrganization', [FormTemplate::class, $organization]);

        try {
            $templates = FormTemplate::query()
                ->whereHas('organizations', function ($query) use ($organization) {
                    $query->where('organization_id', $organization->id);
                })
                ->select(['id', 'title'])
                ->get();

            return response()->json([
                'type' => 'success',
                'message' => 'Templates carregados com sucesso.',
                'data' => $templates,
            ]);

        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    private function processCcgAnalytics(Collection $answers): array
    {
        $analytics = [];

        $analytics['demographic'] = [
            'genderDistribution' => $this->getAnswerCountsForQuestion($answers, 'Gênero'),
            'ageDistribution' => $this->calculateAgeDistribution($answers),
            'householdSize' => $this->getAnswerCountsForQuestion($answers, 'Número de pessoas no domicílio'),
        ];

        $analytics['socioeconomic'] = [
            'educationLevel' => $this->getAnswerCountsForQuestion($answers, 'Escolaridade'),
            'employmentStatus' => $this->getAnswerCountsForQuestion($answers, 'Situação de trabalho'),
            'incomeSources' => $this->getAnswerCountsForQuestion($answers, 'Principais fontes de renda da família'),
        ];

        $analytics['operational'] = [
            'referrals' => $this->getAnswerCountsForQuestion($answers, 'Encaminhamentos realizados'),
            'topNeighborhoods' => $this->getTopNeighborhoods($answers),
        ];

        return $analytics;
    }

    private function processAlbergueAnalytics(Collection $answers): array
    {
        $analytics = [];

        $analytics['mobility_profile'] = [
            'recurrentVisitors' => $this->getAnswerCountsForQuestion($answers, 'Já esteve em Guarapuava em outra(s) ocasião(ões)?'),
            'topOriginCities' => $this->getTopTextAnswers($answers, 'Cidade de Origem'),
            'genderDistribution' => $this->getAnswerCountsForQuestion($answers, 'Qual é seu gênero?'),
            'raceDistribution' => $this->getAnswerCountsForQuestion($answers, 'Você se considera:'),
        ];

        $analytics['health_profile'] = [
            'chronicDiseases' => $this->getAnswerCountsForQuestion($answers, 'Doença Crônica?'),
            'substanceUse' => $this->getAnswerCountsForQuestion($answers, 'Faz uso de substâncias?'),
            'psychologicalConditions' => $this->getAnswerCountsForQuestion($answers, 'Doença Psicológica?'),
            'hasSoughtTreatment' => $this->getAnswerCountsForQuestion($answers, 'Já passou por tratamento para dependência?'),
            'usesMedication' => $this->getAnswerCountsForQuestion($answers, 'Faz uso de medicação?'),
            'medicationAccess' => $this->getAnswerCountsForQuestion($answers, 'Tem acesso a medicação?'),
            'receivesFollowUp' => $this->getAnswerCountsForQuestion($answers, 'Faz acompanhamento?'),
        ];

        $analytics['socioeconomic_profile'] = [
            'hasCadUnico' => $this->getAnswerCountsForQuestion($answers, 'Possui Cad Único?'),
            'socialBenefits' => $this->getAnswerCountsForQuestion($answers, 'Recebe Benefício Social?'),
            'pensionBenefits' => $this->getAnswerCountsForQuestion($answers, 'Recebe Benefício Previdenciário?'),
            'documentation' => [
                'hasRG' => $this->getAnswerCountsForQuestion($answers, 'Possui RG?'),
                'hasCPF' => $this->getAnswerCountsForQuestion($answers, 'Possui CPF?'),
                'hasWorkCard' => $this->getAnswerCountsForQuestion($answers, 'Possui Carteira de Trabalho?'),
            ],
        ];

        return $analytics;
    }

    private function processGenericAnalytics(Collection $answers): Collection
    {
        $analyticsByQuestion = $answers->groupBy('question_description');

        return $analyticsByQuestion->map(function ($questionAnswers) {
            $firstAnswer = $questionAnswers->first();
            if (!$firstAnswer) return null;

            if ($firstAnswer->question_type === 'multiple_choice') {
                return $questionAnswers->groupBy('answer')
                    ->mapWithKeys(fn ($group, $answer) => [$answer ?: 'Não respondido' => $group->count()]);
            }
            return $questionAnswers->pluck('answer');
        })->filter();
    }

    private function getAnswerCountsForQuestion(Collection $answers, string $questionDescription): Collection
    {
        return $answers->where('question_description', $questionDescription)
            ->groupBy('answer')
            ->map(fn ($group) => $group->count());
    }

    private function calculateAgeDistribution(Collection $answers): array
    {
        $birthDates = $answers->where('question_description', 'Data de nascimento')->pluck('answer');
        $ageGroups = [
            '0-17 anos' => 0,
            '18-29 anos' => 0,
            '30-59 anos' => 0,
            '60+ anos' => 0,
        ];

        foreach ($birthDates as $date) {
            $age = Carbon::parse($date)->age;
            if ($age <= 17) $ageGroups['0-17 anos']++;
            elseif ($age <= 29) $ageGroups['18-29 anos']++;
            elseif ($age <= 59) $ageGroups['30-59 anos']++;
            else $ageGroups['60+ anos']++;
        }
        return $ageGroups;
    }

    private function getTopNeighborhoods(Collection $answers): Collection
    {
        return $answers->where('question_description', 'Endereço completo e bairro')
            ->pluck('answer')
            ->map(function ($address) {
                $parts = explode(',', $address);
                return trim($parts[1] ?? 'Bairro não informado');
            })
            ->countBy()
            ->sortDesc()
            ->take(10);
    }

    private function getStartDateFromPeriod(string $period): Carbon
    {
        return match ($period) {
            '7d' => Carbon::now()->subDays(7),
            '15d' => Carbon::now()->subDays(15),
            '30d' => Carbon::now()->subDays(30),
            '3m' => Carbon::now()->subMonths(3),
            '6m' => Carbon::now()->subMonths(6),
            '1y' => Carbon::now()->subYear(),
            default => Carbon::minValue(),
        };
    }

    private function getTopTextAnswers(Collection $answers, string $questionDescription, int $limit = 10): Collection
    {
        return $answers->where('question_description', $questionDescription)
            ->pluck('answer')
            ->map(fn($answer) => trim($answer))
            ->countBy()
            ->sortDesc()
            ->take($limit);
    }
}