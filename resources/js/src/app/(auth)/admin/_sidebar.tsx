import { DashboardLayout } from "@/components/layouts";
import { Roles } from "@/enums/Roles";
import { t } from "@/lang";

import { AiOutlineTeam } from "react-icons/ai";
import { BsHouseHeart } from "react-icons/bs";
import { FaUserSecret } from "react-icons/fa";

export default function AdminSideBar() {
  return (
    <DashboardLayout.SideBar.Root>
      <DashboardLayout.SideBar.LinkGroup
        title={t("roles.admin")}
        icon={<FaUserSecret />}
      >
        <DashboardLayout.SideBar.Link
          href={`/${Roles.ADMIN}/organizations`}
          icon={<BsHouseHeart />}
        >
          {t("page_titles.organizations")}
        </DashboardLayout.SideBar.Link>

        <DashboardLayout.SideBar.Link
          href={`/${Roles.ADMIN}/users`}
          icon={<AiOutlineTeam />}
        >
          {t("page_titles.users")}
        </DashboardLayout.SideBar.Link>
      </DashboardLayout.SideBar.LinkGroup>
    </DashboardLayout.SideBar.Root>
  );
}
