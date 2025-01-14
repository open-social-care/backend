import { HBox, Paper } from "@/components/containers";
import { AiOutlineEdit, AiOutlineFileDone } from "react-icons/ai";

import { t } from "@/lang";

import { Text } from "@/components/ui";
import CardAction from "@/components/ui/CardAction";
import Pagination from "@/components/ui/Pagination";
import { Roles } from "@/enums/Roles";
import subjectRefByOrganizationId from "@/helpers/subjectRefByOrganizationId";
import { Subject } from "@/schemas";
import dayjs from "dayjs";
import { fetchSubjectsAction } from "./_actions";

interface SubjectListProps {
  organizationId: number;
  search?: string;
  page?: number;
}

export default async function SubjectList({ organizationId, search, page }: SubjectListProps) {
  const subjectRef = await subjectRefByOrganizationId(organizationId);

  const { data, pagination } = await fetchSubjectsAction(organizationId, search, page);

  const subjects = Subject.array().parse(data);

  if (subjects.length == 0) {
    return <Text>{t("informations.subjects_not_found", { subject_ref: subjectRef })}</Text>;
  }

  return (
    <>
      {subjects.map((subject) => (
        <Paper
          className="mt-1"
          key={subject.id}
        >
          <Text className="font-semibold">{subject.name}</Text>

          <Text>
            {t("labels.birth_date")}
            {": "}
            {dayjs(subject.birth_date).format("DD/MM/YYYY")}
          </Text>

          <HBox className="mt-4 flex-col justify-between gap-4 sm:flex-row">
            <div>
              {subject.last_form_answer_date && (
                <Text className="text-gray-400">
                  {t("labels.last_form_answer_date")}
                  {": "}
                  {dayjs(subject.last_form_answer_date).format("DD/MM/YYYY HH:mm")}
                </Text>
              )}
            </div>

            <HBox className="justify-end gap-4">
              <CardAction
                title={t("page_titles.filled_forms")}
                href={`/${Roles.SOCIAL_ASSISTANT}/organizations/${organizationId}/subjects/${subject.id}/form-answers`}
                icon={<AiOutlineFileDone />}
              />

              <CardAction
                title={t("general_actions.edit")}
                href={`/${Roles.SOCIAL_ASSISTANT}/organizations/${organizationId}/subjects/${subject.id}/edit`}
                icon={<AiOutlineEdit />}
              />
            </HBox>
          </HBox>
        </Paper>
      ))}

      <Pagination paginate={pagination} />
    </>
  );
}
