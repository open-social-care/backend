<?php

namespace Database\Seeders;

use App\Models\FormTemplate;
use App\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlbergueFormTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Iniciando o seeder do template do Albergue...');
        DB::transaction(function () {
            $formTemplate = FormTemplate::updateOrCreate(
                ['title' => 'Plano de Acompanhamento - Albergue'],
                [
                    'description' => 'Template padrão baseado na ficha de atendimento do Albergue.',
                    'has_file_uploads' => false,
                ]
            );
            $this->command->info("Template '{$formTemplate->title}' criado/encontrado com ID: {$formTemplate->id}.");

            $formTemplate->shortQuestions()->createMany([
                ['description' => 'Nome', 'answer_required' => true, 'data_type' => 'short_question'],
                ['description' => 'Data de Nascimento', 'answer_required' => true, 'data_type' => 'short_question'],
                ['description' => 'Naturalidade', 'answer_required' => false, 'data_type' => 'short_question'],
                ['description' => 'Filiação', 'answer_required' => false, 'data_type' => 'short_question'],
                ['description' => 'RG', 'answer_required' => false, 'data_type' => 'short_question'],
                ['description' => 'CPF', 'answer_required' => false, 'data_type' => 'short_question'],
                ['description' => 'Cidade de Origem', 'answer_required' => false, 'data_type' => 'short_question'],
                ['description' => 'Período que permaneceu na cidade de origem', 'answer_required' => false, 'data_type' => 'short_question'],
                ['description' => 'Observações sobre estadias anteriores em Guarapuava', 'answer_required' => false, 'data_type' => 'short_question'],
                ['description' => 'Cidade do CadÚnico', 'answer_required' => false, 'data_type' => 'short_question'],
                ['description' => 'Valor do Benefício Social', 'answer_required' => false, 'data_type' => 'short_question'],
                ['description' => 'Valor do Benefício Previdenciário', 'answer_required' => false, 'data_type' => 'short_question'],
                ['description' => 'Profissão', 'answer_required' => false, 'data_type' => 'short_question'],
                ['description' => 'Último Emprego', 'answer_required' => false, 'data_type' => 'short_question'],
                ['description' => 'Data de saída do último emprego', 'answer_required' => false, 'data_type' => 'short_question'],
                ['description' => 'Local do último emprego', 'answer_required' => false, 'data_type' => 'short_question'],
                ['description' => 'Experiências Profissionais', 'answer_required' => false, 'data_type' => 'short_question'],
                ['description' => 'Áreas profissionais que gostaria de conhecer', 'answer_required' => false, 'data_type' => 'short_question'],
                ['description' => 'Composição Familiar (Nome, Parentesco, Idade, Cidade)', 'answer_required' => false, 'data_type' => 'short_question'],
                ['description' => 'Observações sobre RG/CPF', 'answer_required' => false, 'data_type' => 'short_question'],
                ['description' => 'Observações sobre Título/Carteira de Trabalho', 'answer_required' => false, 'data_type' => 'short_question'],
            ]);

            $formTemplate->multipleChoiceQuestions()->create(['description' => 'Já esteve em Guarapuava em outra(s) ocasião(ões)?', 'answer_required' => false, 'data_type' => 'multiple_choice'])
                ->multipleChoiceOptions()->createMany([['description' => 'Sim'], ['description' => 'Não']]);

            $formTemplate->multipleChoiceQuestions()->create(['description' => 'Estado Civil', 'answer_required' => false, 'data_type' => 'multiple_choice'])
                ->multipleChoiceOptions()->createMany([['description' => 'Solteiro(a)'], ['description' => 'Casado(a)'], ['description' => 'Divorciado(a)'], ['description' => 'Viúvo(a)'], ['description' => 'União Estável']]);

            $formTemplate->multipleChoiceQuestions()->create(['description' => 'Qual é seu gênero?', 'answer_required' => true, 'data_type' => 'multiple_choice'])
                ->multipleChoiceOptions()->createMany([['description' => 'Feminino'], ['description' => 'Masculino'], ['description' => 'Outro']]);

            $formTemplate->multipleChoiceQuestions()->create(['description' => 'Você se considera:', 'answer_required' => true, 'data_type' => 'multiple_choice'])
                ->multipleChoiceOptions()->createMany([['description' => 'Branco(a)'], ['description' => 'Negro(a)'], ['description' => 'Indígena'], ['description' => 'Pardo(a)'], ['description' => 'Amarelo(a)']]);

            $formTemplate->multipleChoiceQuestions()->create(['description' => 'Possui Cad Único?', 'answer_required' => true, 'data_type' => 'multiple_choice'])
                ->multipleChoiceOptions()->createMany([['description' => 'Sim'], ['description' => 'Não']]);

            $formTemplate->multipleChoiceQuestions()->create(['description' => 'Recebe Benefício Social?', 'answer_required' => true, 'data_type' => 'multiple_choice'])
                ->multipleChoiceOptions()->createMany([['description' => 'Auxílio Brasil'], ['description' => 'Auxílio Emergencial'], ['description' => 'BPC'], ['description' => 'Não']]);

            $formTemplate->multipleChoiceQuestions()->create(['description' => 'Recebe Benefício Previdenciário?', 'answer_required' => true, 'data_type' => 'multiple_choice'])
                ->multipleChoiceOptions()->createMany([['description' => 'Aposentadoria'], ['description' => 'Pensão por morte'], ['description' => 'Seguro Desemprego'], ['description' => 'Não']]);

            $formTemplate->multipleChoiceQuestions()->create(['description' => 'Escolaridade', 'answer_required' => true, 'data_type' => 'multiple_choice'])
                ->multipleChoiceOptions()->createMany([['description' => 'Não Alfabetizado'], ['description' => 'Fundamental Incompleto'], ['description' => 'Fundamental Completo'], ['description' => 'Ensino Médio Incompleto'], ['description' => 'Ensino Médio Completo'], ['description' => 'Superior Incompleto'], ['description' => 'Superior Completo'], ['description' => 'Pós-Graduação']]);

            $formTemplate->multipleChoiceQuestions()->create(['description' => 'Possui Cartão SUS?', 'answer_required' => false, 'data_type' => 'multiple_choice'])
                ->multipleChoiceOptions()->createMany([['description' => 'Sim'], ['description' => 'Não']]);

            $formTemplate->multipleChoiceQuestions()->create(['description' => 'Doença Crônica?', 'answer_required' => false, 'data_type' => 'multiple_choice'])
                ->multipleChoiceOptions()->createMany([['description' => 'Sim'], ['description' => 'Não']]);

            $formTemplate->multipleChoiceQuestions()->create(['description' => 'Faz uso de substâncias?', 'answer_required' => false, 'data_type' => 'multiple_choice'])
                ->multipleChoiceOptions()->createMany([['description' => 'Não'], ['description' => 'Cigarro'], ['description' => 'Álcool'], ['description' => 'Drogas'], ['description' => 'Outro']]);

            $formTemplate->multipleChoiceQuestions()->create(['description' => 'Já passou por tratamento para dependência?', 'answer_required' => false, 'data_type' => 'multiple_choice'])
                ->multipleChoiceOptions()->createMany([['description' => 'Sim'], ['description' => 'Não']]);

            $formTemplate->multipleChoiceQuestions()->create(['description' => 'Doença Psicológica?', 'answer_required' => false, 'data_type' => 'multiple_choice'])
                ->multipleChoiceOptions()->createMany([['description' => 'Sim'], ['description' => 'Não']]);

            $formTemplate->multipleChoiceQuestions()->create(['description' => 'Possui RG?', 'answer_required' => true, 'data_type' => 'multiple_choice'])
                ->multipleChoiceOptions()->createMany([['description' => 'Sim'], ['description' => 'Não']]);

            $formTemplate->multipleChoiceQuestions()->create(['description' => 'Possui CPF?', 'answer_required' => true, 'data_type' => 'multiple_choice'])
                ->multipleChoiceOptions()->createMany([['description' => 'Sim'], ['description' => 'Não']]);

            $formTemplate->multipleChoiceQuestions()->create(['description' => 'Possui Título de Eleitor?', 'answer_required' => true, 'data_type' => 'multiple_choice'])
                ->multipleChoiceOptions()->createMany([['description' => 'Sim'], ['description' => 'Não']]);

            $formTemplate->multipleChoiceQuestions()->create(['description' => 'Possui Carteira de Trabalho?', 'answer_required' => true, 'data_type' => 'multiple_choice'])
                ->multipleChoiceOptions()->createMany([['description' => 'Sim'], ['description' => 'Não']]);

            $organizationIds = Organization::all()->pluck('id');
            if ($organizationIds->isNotEmpty()) {
                $formTemplate->organizations()->sync($organizationIds);
                $this->command->info("Template associado a {$organizationIds->count()} organização(ões).");
            } else {
                $this->command->warn('Nenhuma organização encontrada para associar o template.');
            }
        });
        $this->command->info('Seeder do template do Albergue concluído com sucesso!');
    }
}