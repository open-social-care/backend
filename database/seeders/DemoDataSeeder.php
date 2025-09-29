<?php

namespace Database\Seeders;

use App\Models\FormAnswer;
use App\Models\FormTemplate;
use App\Models\Organization;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $cggTemplate = FormTemplate::where('title', 'Ficha de Atendimento CCG')->first();
        $socialAssistant = User::first();
        $organization = Organization::first();

        if (! $cggTemplate || ! $socialAssistant || ! $organization) {
            $this->command->error('Template CCG, Usuário ou Organização não encontrado. Rode o CggFormTemplateSeeder primeiro.');
            return;
        }

        $cggTemplate->load(['shortQuestions', 'multipleChoiceQuestions.multipleChoiceOptions']);
        $shortQuestions = $cggTemplate->shortQuestions;
        $multipleChoiceQuestions = $cggTemplate->multipleChoiceQuestions;

        $this->command->info('Criando 50 atendidos (subjects)...');
        $subjects = Subject::factory(50)
            ->make()
            ->each(function ($subject) use ($organization, $socialAssistant) {
                $subject->organization_id = $organization->id;
                $subject->user_id = $socialAssistant->id;
                $subject->save();
            });

        $this->command->info('Preenchendo formulários para cada atendido...');
        $progressBar = $this->command->getOutput()->createProgressBar($subjects->count());
        $progressBar->start();

        foreach ($subjects as $subject) {
            $numberOfAnswers = rand(1, 3);
            for ($i = 0; $i < $numberOfAnswers; $i++) {
                $formAnswer = FormAnswer::create([
                    'user_id' => $socialAssistant->id,
                    'subject_id' => $subject->id,
                    'form_template_id' => $cggTemplate->id,
                ]);

                foreach ($shortQuestions as $question) {
                    $answerText = null;

                    switch ($question->description) {
                        case 'Nome completo do atendido':
                            $answerText = $subject->name;
                            break;
                        case 'Data de nascimento':
                            $answerText = $subject->birth_date;
                            break;
                        case 'Endereço completo e bairro':
                            $answerText = fake()->streetAddress() . ', ' . fake()->city();
                            break;
                        case 'Número de pessoas no domicílio':
                            $answerText = fake()->numberBetween(1, 6);
                            break;
                        case 'Principais fontes de renda da família':
                            $answerText = fake()->randomElement(['Salário', 'Bolsa Família', 'Aposentadoria', 'Trabalho Informal']);
                            break;
                        case 'Encaminhamentos realizados':
                            $answerText = fake()->randomElement(['CRAS', 'CREAS', 'Posto de Saúde', 'Nenhum']);
                            break;
                        case 'Observações adicionais':
                            $answerText = fake()->boolean(25) ? fake()->sentence() : null;
                            break;
                    }

                    if ($answerText) {
                        $formAnswer->shortAnswers()->create([
                            'short_question_id' => $question->id,
                            'subject_id' => $subject->id,
                            'answer' => $answerText,
                        ]);
                    }
                }

                foreach ($multipleChoiceQuestions as $question) {
                    $randomOption = $question->multipleChoiceOptions->random();
                    $formAnswer->multipleChoiceAnswers()->create([
                        'multiple_choice_question_id' => $question->id,
                        'subject_id' => $subject->id,
                        'answer' => $randomOption->description,
                    ]);
                }
            }
            $progressBar->advance();
        }
        $progressBar->finish();
        $this->command->info("\nDados de demonstração criados com sucesso!");
    }
}