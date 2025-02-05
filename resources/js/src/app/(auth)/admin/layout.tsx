import { DashboardLayout } from "@/components/layouts";
import type { Metadata } from "next";
import { PropsWithChildren } from "react";
import AdminSideBar from "./_sidebar";

export const metadata: Metadata = {
  title: "Admin",
};

export default function layout({ children }: PropsWithChildren) {
  return (
    <DashboardLayout.Root>
      <DashboardLayout.Navbar />

      <DashboardLayout.Content>
        <AdminSideBar />

        <DashboardLayout.Main>{children}</DashboardLayout.Main>
      </DashboardLayout.Content>

      <DashboardLayout.Footer />
    </DashboardLayout.Root>
  );
}
