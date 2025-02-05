import { DashboardLayout } from "@/components/layouts";
import type { Metadata } from "next";
import { PropsWithChildren } from "react";
import SocialAssistantSideBar from "./_sidebar";

export const metadata: Metadata = {
  title: "Social Assistant",
};

export default function layout({ children }: PropsWithChildren) {
  return (
    <DashboardLayout.Root>
      <DashboardLayout.Navbar />

      <DashboardLayout.Content>
        <SocialAssistantSideBar />

        <DashboardLayout.Main>{children}</DashboardLayout.Main>
      </DashboardLayout.Content>

      <DashboardLayout.Footer />
    </DashboardLayout.Root>
  );
}
