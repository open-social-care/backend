import { Paper } from "@/components/containers";
import { Heading } from "@/components/ui";
import { t } from "@/lang";
import { User } from "@/schemas";
import { fetchUserAction } from "./_actions";
import { UpdateUserForm } from "./_form";

interface PageProps {
  params: {
    userId: number;
  };
}

export default async function page({ params }: PageProps) {
  const { data: userData } = await fetchUserAction(params.userId);

  const user = User.parse(userData);

  return (
    <>
      <Heading h1>{t("page_titles.edit_user")}</Heading>

      <Paper className="mt-4">
        <UpdateUserForm user={user} />
      </Paper>
    </>
  );
}
