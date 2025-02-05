import { ActionFlashes } from "@/action-flash/ActionFlashes";
import { HBox, VBox } from "@/components/containers";
import { Button, Heading, Search, Skeleton } from "@/components/ui";
import { t } from "@/lang";
import TemplateList from "./_templates";

interface PageProps {
  params: {
    organizationId: number;
  };
  searchParams: {
    page: number;
    search: string;
  };
}

export default function page({ params, searchParams }: PageProps) {
  return (
    <>
      <ActionFlashes />

      <HBox className="justify-between">
        <Heading h1>{t("page_titles.form_templates")}</Heading>

        <Button href={`/manager/organizations/${params.organizationId}/form-templates/create`}>
          {t("general_actions.create")}
        </Button>
      </HBox>

      <VBox className="mt-5">
        <Search />

        <Skeleton
          className="h-28"
          length={5}
        >
          <TemplateList
            organizationId={params.organizationId}
            {...searchParams}
          />
        </Skeleton>
      </VBox>
    </>
  );
}
