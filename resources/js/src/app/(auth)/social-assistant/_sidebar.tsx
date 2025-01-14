import { DashboardLayout } from "@/components/layouts";
import { t } from "@/lang";

import auth from "@/auth";
import { Roles } from "@/enums/Roles";
import { BsHouseHeart } from "react-icons/bs";
import { FaUserCheck } from "react-icons/fa";
import OrganizationLinks from "./_organization-links";
import SubjectLinks from "./_subject-links";

export default async function SocialAssistantSideBar() {
  const { user } = await auth();

  return (
    <DashboardLayout.SideBar.Root>
      <DashboardLayout.SideBar.LinkGroup
        title={t(`roles.${Roles.SOCIAL_ASSISTANT}`)}
        icon={<FaUserCheck />}
      >
        {/* <DashboardLayout.SideBar.Link
          href="/social-assistant"
          icon={<AiOutlineHome />}
        >
          Início
        </DashboardLayout.SideBar.Link> */}

        <DashboardLayout.SideBar.Link
          href={`/${Roles.SOCIAL_ASSISTANT}/organizations`}
          icon={<BsHouseHeart />}
        >
          {t("page_titles.organizations")}
        </DashboardLayout.SideBar.Link>
      </DashboardLayout.SideBar.LinkGroup>

      <OrganizationLinks user={user} />

      <SubjectLinks />
    </DashboardLayout.SideBar.Root>
  );
}
