import { Paper } from "@/components/containers";
import { Heading } from "@/components/ui";
import { t } from "@/lang";
import { FormTemplate } from "@/schemas";
import { fetchFormTemplateAction } from "../_actions";
import { UpdateFormTemplateForm } from "./_form";

interface PageProps {
  params: {
    organizationId: number;
    templateId: number;
  };
}

export default async function page({ params }: PageProps) {
  const { data } = await fetchFormTemplateAction(params.templateId);

  const formTemplate = FormTemplate.parse(data);

  return (
    <>
      <Heading h1>{t("page_titles.edit_form_template")}</Heading>

      <Paper className="mt-4">
        <UpdateFormTemplateForm
          template={formTemplate}
          {...params}
        />
      </Paper>
    </>
  );
}
