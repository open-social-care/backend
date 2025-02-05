"use client";

import CardAction from "@/components/ui/CardAction";
import { t } from "@/lang";
import { AiOutlineDelete } from "react-icons/ai";
import { removeFormTemplateAction } from "./_actions";

export type RemoveTemplateActionValues = {
  templateId: number;
};

export default function RemoveTemplateAction(props: RemoveTemplateActionValues) {
  return (
    <CardAction
      title={t("general_actions.remove")}
      icon={<AiOutlineDelete />}
      onClick={() => removeFormTemplateAction(props.templateId)}
    />
  );
}
