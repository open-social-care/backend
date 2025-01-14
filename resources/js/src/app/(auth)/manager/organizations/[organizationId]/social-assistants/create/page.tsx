import { Paper } from "@/components/containers";
import { Heading } from "@/components/ui";
import { t } from "@/lang";
import { CreateUserForm } from "./_form";

export default function page() {
  return (
    <>
      <Heading h2>{t("page_titles.create_social_assistant")}</Heading>

      <Paper className="mt-4">
        <CreateUserForm />
      </Paper>
    </>
  );
}
