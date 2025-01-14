"use client";

import CardAction from "@/components/ui/CardAction";
import { t } from "@/lang";
import { AiOutlineUserDelete } from "react-icons/ai";
import { DissociateUserActionValues, dissociateUserAction } from "./_actions";

export default function DissociateAction(props: DissociateUserActionValues) {
  return (
    <CardAction
      icon={<AiOutlineUserDelete />}
      title={t("general_actions.dissociate")}
      onClick={() => dissociateUserAction(props)}
    />
  );
}
