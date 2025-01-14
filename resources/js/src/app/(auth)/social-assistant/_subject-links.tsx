"use client";

import { DashboardLayout } from "@/components/layouts";
import { Roles } from "@/enums/Roles";
import { t } from "@/lang";
import { Subject } from "@/schemas";
import { useParams } from "next/navigation";
import { useEffect, useState } from "react";
import { AiOutlineFileDone } from "react-icons/ai";
import { FaUser } from "react-icons/fa";
import { fetchSubjectAction } from "./_actions";

export default function SubjectLinks() {
  const { organizationId, subjectId } = useParams<{ organizationId?: string; subjectId: string }>();

  const [subject, setSubject] = useState<Subject>();

  const fetchSubject = async () => {
    const { data } = await fetchSubjectAction(subjectId);

    setSubject(data);
  };

  useEffect(() => {
    fetchSubject();
  }, []);

  if (!organizationId || !subjectId) return;

  return (
    <DashboardLayout.SideBar.LinkGroup
      title={subject?.name || "..."}
      icon={<FaUser />}
    >
      <DashboardLayout.SideBar.Link
        href={`/${Roles.SOCIAL_ASSISTANT}/organizations/${organizationId}/subjects/${subjectId}/form-answers`}
        icon={<AiOutlineFileDone />}
      >
        {t("page_titles.filled_forms")}
      </DashboardLayout.SideBar.Link>
    </DashboardLayout.SideBar.LinkGroup>
  );
}
