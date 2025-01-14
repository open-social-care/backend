"use client";

import { HBox, VBox } from "@/components/containers";
import { CardAction, Heading, Text } from "@/components/ui";
import useQueryParam from "@/hooks/useQueryParam";
import { t } from "@/lang";
import { FormTemplate } from "@/schemas";
import { AiOutlineSelect, AiOutlineSwap } from "react-icons/ai";

type SelecTemplateProps = {
  templates: FormTemplate[];
};

export default function SelecTemplate({ templates }: SelecTemplateProps) {
  const { value, set, del } = useQueryParam({ queryParam: "template" });

  if (templates.length == 0) {
    return <Text>{t("informations.form_templates_not_found")}</Text>;
  }

  return (
    <>
      {value && (
        <HBox className="justify-between">
          <Heading h2>{templates.find((t) => t.id.toString() == value)?.title}</Heading>

          <CardAction
            title=" Trocar template"
            icon={<AiOutlineSwap />}
            onClick={del}
          />
        </HBox>
      )}

      {!value && (
        <>
          <Heading h2>{t("labels.select_a_template")}</Heading>

          <div className="mt-2">
            {templates.map((template) => (
              <VBox
                key={template.id}
                className="rounded-lg px-4 py-2 hover:bg-gray-50 sm:flex-row sm:justify-between"
              >
                <VBox>
                  <Text className="font-semibold">{template.title}</Text>
                </VBox>

                <HBox className="justify-end">
                  <CardAction
                    icon={<AiOutlineSelect />}
                    title="Usar template"
                    onClick={() => set(template.id.toString())}
                  />
                </HBox>
              </VBox>
            ))}
          </div>
        </>
      )}
    </>
  );
}
