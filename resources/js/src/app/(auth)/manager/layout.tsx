import { DashboardLayout } from "@/components/layouts";
import { t } from "@/lang";
import type { Metadata } from "next";
import { PropsWithChildren } from "react";
import ManagerSideBar from "./_sidebar";

export const metadata: Metadata = {
  title: t("roles.manager"),
};

export default function layout({ children }: PropsWithChildren) {
  return (
    <DashboardLayout.Root>
      <DashboardLayout.Navbar />

      <DashboardLayout.Content>
        <ManagerSideBar />

        <DashboardLayout.Main>{children}</DashboardLayout.Main>
      </DashboardLayout.Content>

      <DashboardLayout.Footer />
    </DashboardLayout.Root>
  );
}
