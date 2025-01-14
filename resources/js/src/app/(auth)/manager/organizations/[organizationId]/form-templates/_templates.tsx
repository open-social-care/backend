import { HBox, Paper } from "@/components/containers";
import { AiOutlineSetting } from "react-icons/ai";

import { t } from "@/lang";

import { Text } from "@/components/ui";
import CardAction from "@/components/ui/CardAction";
import Pagination from "@/components/ui/Pagination";
import { FormTemplate } from "@/schemas/FormTemplate";
import { fetchFormTemplatesAction } from "./_actions";
import RemoveTemplateAction from "./_remove-template";

interface TemplateListProps {
  organizationId: number;
  search?: string;
  page?: number;
}

export default async function TemplateList({ organizationId, search, page }: TemplateListProps) {
  const { data, pagination } = await fetchFormTemplatesAction(organizationId, search, page);

  const formTemplates = FormTemplate.array().parse(data);

  if (formTemplates.length == 0) {
    return <Text>{t("informations.form_templates_not_found")}</Text>;
  }

  return (
    <>
      {formTemplates.map((formTemplate) => (
        <Paper
          className="mt-1"
          key={formTemplate.id}
        >
          <Text className="font-semibold">{formTemplate.title}</Text>
          <Text className="text-sm">{formTemplate.description}</Text>

          <HBox className="mt-4 justify-end gap-4">
            <CardAction
              title={t("general_actions.manage")}
              href={`/manager/organizations/${organizationId}/form-templates/${formTemplate.id}`}
              icon={<AiOutlineSetting />}
            />

            <RemoveTemplateAction templateId={formTemplate.id} />
          </HBox>
        </Paper>
      ))}

      <Pagination paginate={pagination} />
    </>
  );
}
