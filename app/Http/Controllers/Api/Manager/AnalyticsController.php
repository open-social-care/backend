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

        if ($formTemplate->title === 'Ficha de Atendimento CCG') {
            $chartData = $this->processCcgAnalytics($answers);
        } else {
            $chartData = $this->processGenericAnalytics($answers);
        }

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
}