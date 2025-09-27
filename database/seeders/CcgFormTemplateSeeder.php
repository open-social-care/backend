<?php

namespace Database\Seeders;

use App\Models\FormTemplate;
use App\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CcgFormTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $formTemplate = FormTemplate::create([
                'title' => 'Ficha de Atendimento CCG',
                'description' => 'Template padrão baseado na ficha de atendimento do Conselho da Comunidade de Guarapuava.',
                'has_file_uploads' => false,
            ]);

            $formTemplate->shortQuestions()->createMany([
                ['description' => 'Nome completo do atendido', 'answer_required' => true, 'data_type' => 'short_question'],
                ['description' => 'Data de nascimento', 'answer_required' => true, 'data_type' => 'short_question'],
                ['description' => 'Endereço completo e bairro', 'answer_required' => true, 'data_type' => 'short_question'],
                ['description' => 'Número de pessoas no domicílio', 'answer_required' => true, 'data_type' => 'short_question'],
                ['description' => 'Principais fontes de renda da família', 'answer_required' => true, 'data_type' => 'short_question'],
                ['description' => 'Encaminhamentos realizados', 'answer_required' => false, 'data_type' => 'short_question'],
                ['description' => 'Observações adicionais', 'answer_required' => false, 'data_type' => 'short_question'],
            ]);

            $questionGenero = $formTemplate->multipleChoiceQuestions()->create([
                'description' => 'Gênero',
                'answer_required' => true,
                'data_type' => 'multiple_choice'
            ]);
            $questionGenero->multipleChoiceOptions()->createMany([
                ['description' => 'Masculino'],
                ['description' => 'Feminino'],
                ['description' => 'Outro'],
            ]);

            $questionEscolaridade = $formTemplate->multipleChoiceQuestions()->create([
                'description' => 'Escolaridade',
                'answer_required' => true,
                'data_type' => 'multiple_choice'
            ]);
            $questionEscolaridade->multipleChoiceOptions()->createMany([
                ['description' => 'Analfabeto'],
                ['description' => 'Fundamental Incompleto'],
                ['description' => 'Fundamental Completo'],
                ['description' => 'Médio Incompleto'],
                ['description' => 'Médio Completo'],
                ['description' => 'Superior Incompleto'],
                ['description' => 'Superior Completo'],
            ]);

            $questionTrabalho = $formTemplate->multipleChoiceQuestions()->create([
                'description' => 'Situação de trabalho',
                'answer_required' => true,
                'data_type' => 'multiple_choice'
            ]);
            $questionTrabalho->multipleChoiceOptions()->createMany([
                ['description' => 'Empregado (CLT)'],
                ['description' => 'Desempregado'],
                ['description' => 'Trabalho Informal / Autônomo'],
                ['description' => 'Aposentado / Pensionista'],
            ]);

            $organizationIds = Organization::all()->pluck('id');

            if ($organizationIds->isNotEmpty()) {
                $formTemplate->organizations()->attach($organizationIds);
            }
        });
    }
}