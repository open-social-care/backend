import { ActionFlashes } from "@/action-flash/ActionFlashes";
import { HBox, VBox } from "@/components/containers";
import { Button, Heading, Skeleton } from "@/components/ui";
import { t } from "@/lang";
import FormAnswers from "./_form-answers";

interface PageProps {
  params: {
    subjectId: number;
  };
  searchParams: {
    page: number;
    search: string;
  };
}

export default async function page({ params, searchParams }: PageProps) {
  return (
    <>
      <ActionFlashes />

      <HBox className="justify-between">
        <Heading h1>{t("page_titles.filled_forms")}</Heading>

        <Button href="form-answers/create">{t("general_actions.create")}</Button>
      </HBox>

      <VBox className="mt-5">
        {/* <Search /> */}

        <Skeleton
          className="h-28"
          length={5}
        >
          <FormAnswers
            subjectId={params.subjectId}
            {...searchParams}
          />
        </Skeleton>
      </VBox>
    </>
  );
}
