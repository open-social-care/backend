"use client";

import { DashboardLayout } from "@/components/layouts";
import { Roles } from "@/enums/Roles";
import { t } from "@/lang";
import { User } from "@/schemas";
import { useParams } from "next/navigation";
import { AiOutlineSetting, AiOutlineSnippets } from "react-icons/ai";
import { BsFillHouseGearFill } from "react-icons/bs";

type OrganizationLinksProps = {
  user: User;
};

export default function OrganizationLinks({ user }: OrganizationLinksProps) {
  const { organizationId } = useParams<{ organizationId?: string }>();

  const organization = user.organizations?.find((org) => organizationId === org.id.toString());

  if (!organizationId) return;

  return (
    <DashboardLayout.SideBar.LinkGroup
      title={organization?.name || t("labels.organization")}
      icon={<BsFillHouseGearFill />}
    >
      <DashboardLayout.SideBar.Link
        href={`/${Roles.MANAGER}/organizations/${organizationId}`}
        icon={<AiOutlineSetting />}
      >
        {t("general_actions.manage")}
      </DashboardLayout.SideBar.Link>

      <DashboardLayout.SideBar.Link
        href={`/${Roles.MANAGER}/organizations/${organizationId}/form-templates`}
        icon={<AiOutlineSnippets />}
      >
        {t("labels.templates")}
      </DashboardLayout.SideBar.Link>
    </DashboardLayout.SideBar.LinkGroup>
  );
}
