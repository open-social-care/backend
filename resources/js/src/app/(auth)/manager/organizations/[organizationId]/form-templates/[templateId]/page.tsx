import { ActionFlashes } from "@/action-flash/ActionFlashes";
import { HBox, VBox } from "@/components/containers";
import { Button, Heading, Skeleton } from "@/components/ui";
import { t } from "@/lang";
import { FormTemplate } from "@/schemas";
import { fetchFormTemplateAction } from "./_actions";
import QuestionList from "./questions/_questions";

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
      <ActionFlashes />

      <HBox className="justify-between">
        <Heading h1>{formTemplate.title}</Heading>

        <Button
          href={`/manager/organizations/${params.organizationId}/form-templates/${formTemplate.id}/edit`}
        >
          {t("general_actions.edit")}
        </Button>
      </HBox>

      <VBox className="mt-5">
        <Skeleton
          className="h-28"
          length={5}
        >
          <QuestionList {...params} />
        </Skeleton>
      </VBox>
    </>
  );
}
