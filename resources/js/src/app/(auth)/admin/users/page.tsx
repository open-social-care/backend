import { ActionFlashes } from "@/action-flash/ActionFlashes";
import { HBox, VBox } from "@/components/containers";
import { Heading } from "@/components/ui";
import Button from "@/components/ui/Button";
import Search from "@/components/ui/Search";
import Skeleton from "@/components/ui/Skeleton";
import { t } from "@/lang";
import Users from "./_users";

interface PageProps {
  searchParams: {
    page: number;
    search: string;
  };
}

export default function page({ searchParams }: PageProps) {
  return (
    <>
      <ActionFlashes />

      <HBox className="justify-between">
        <Heading h1>{t("page_titles.users")}</Heading>

        <Button href="/admin/users/create">{t("general_actions.create")}</Button>
      </HBox>

      <VBox className="mt-5">
        <Search />

        <Skeleton
          className="h-28"
          length={5}
        >
          <Users {...searchParams} />
        </Skeleton>
      </VBox>
    </>
  );
}
