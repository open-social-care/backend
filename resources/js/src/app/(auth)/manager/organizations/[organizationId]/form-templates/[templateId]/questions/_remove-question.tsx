"use client";

import CardAction from "@/components/ui/CardAction";
import { t } from "@/lang";
import { AiOutlineDelete } from "react-icons/ai";
import { removeQuestionAction } from "./_actions";

export type RemoveQuestionActionValues = {
  templateId: number;
  questionId: number;
};

export default function RemoveQuestionAction(props: RemoveQuestionActionValues) {
  return (
    <CardAction
      title={t("general_actions.remove")}
      icon={<AiOutlineDelete />}
      onClick={() => removeQuestionAction(props.templateId, props.questionId)}
    />
  );
}
