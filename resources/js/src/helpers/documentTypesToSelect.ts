import { SelectItem } from "@/components/form/Select";
import { DocumentTypes } from "@/enums/DocumentTypes";
import { t } from "@/lang";

export default function documentTypesToSelect(): SelectItem[] {
  return Object.values(DocumentTypes).map((type) => {
    return {
      label: t(`document_types.${type}`),
      value: type,
    };
  });
}
