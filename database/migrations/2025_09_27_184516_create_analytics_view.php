<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW analytics_view AS
            (
                SELECT
                    fa.form_template_id,
                    sa.short_question_id    AS question_id,
                    sq.description          AS question_description,
                    sq.data_type            AS question_type,
                    sa.answer               AS answer,
                    fa.subject_id,
                    fa.user_id,
                    fa.created_at
                FROM form_answers AS fa
                JOIN short_answers AS sa ON fa.id = sa.form_answer_id
                JOIN short_questions AS sq ON sa.short_question_id = sq.id
                WHERE sa.answer IS NOT NULL

                UNION ALL

                SELECT
                    fa.form_template_id,
                    mca.multiple_choice_question_id AS question_id,
                    mcq.description                 AS question_description,
                    mcq.data_type                   AS question_type,
                    mca.answer                      AS answer,
                    fa.subject_id,
                    fa.user_id,
                    fa.created_at
                FROM form_answers AS fa
                JOIN multiple_choice_answers AS mca ON fa.id = mca.form_answer_id
                JOIN multiple_choice_questions AS mcq ON mca.multiple_choice_question_id = mcq.id
                WHERE mca.answer IS NOT NULL
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS analytics_view");
    }
};