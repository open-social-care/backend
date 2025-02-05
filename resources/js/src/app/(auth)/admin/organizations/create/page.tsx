import { Paper } from "@/components/containers";
import { Heading } from "@/components/ui";
import { t } from "@/lang";
import { CreateOrganizationForm } from "./_form";

export default function page() {
  return (
    <>
      <Heading h1>{t("page_titles.create_organization")}</Heading>

      <Paper className="mt-4">
        <CreateOrganizationForm />
      </Paper>
    </>
  );
}
