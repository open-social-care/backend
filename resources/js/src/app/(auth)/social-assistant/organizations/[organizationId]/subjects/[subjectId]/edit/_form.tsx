"use client";

import Form from "@/components/form";
import { testIDs } from "@/e2e/_testIDs";
import { t } from "@/lang";
import { useFormState } from "react-dom";

import { Heading } from "@/components/ui";
import { DocumentTypes } from "@/enums/DocumentTypes";
import { State, SubjectDetails } from "@/schemas";
import { City } from "@/schemas/City";
import { SkinColor } from "@/schemas/SkinColor";
import dayjs from "dayjs";
import { useParams } from "next/navigation";
import { updateSubjectAction } from "./_actions";

interface UpdateSubjectFormProps {
  subject: SubjectDetails;
  skinColors: SkinColor[];
  states: State[];
  cities: City[];
}

export function UpdateSubjectForm({ subject, skinColors, states, cities }: UpdateSubjectFormProps) {
  const { organizationId } = useParams<{ organizationId: string }>();

  const [state, formAction] = useFormState(
    updateSubjectAction.bind(null, organizationId, subject.id),
    undefined,
  );

  const address = subject.addresses.findLast((a) => a);

  return (
    <Form action={formAction}>
      {state && (
        <Form.FlashMessage
          type={state.type}
          message={state.message}
        />
      )}

      <Heading
        h2
        className={state && "mt-2"}
      >
        {t("labels.personal_information")}
      </Heading>

      <Form.Input
        name="name"
        label={t("labels.name")}
        placeholder={t("labels.name")}
        withAsterisk
        errors={state?.errors?.["name"]}
        defaultValue={subject.name}
      />

      <Form.Input
        name="birth_date"
        label={t("labels.birth_date")}
        placeholder="00/00/0000"
        mask="00/00/0000"
        withAsterisk
        errors={state?.errors?.["birth_date"]}
        defaultValue={dayjs(subject.birth_date).format("DD/MM/YYYY")}
      />

      <Form.Input
        name="nationality"
        label={t("labels.nationality")}
        placeholder={t("labels.nationality")}
        errors={state?.errors?.["nationality"]}
        defaultValue={subject.nationality!}
      />

      <Form.Input
        name="phone"
        label={t("labels.phone")}
        placeholder="(00) 0000-0000"
        mask={[{ mask: "(00) 0000-0000" }, { mask: "(00) 00000-0000" }]}
        errors={state?.errors?.["phone"]}
        defaultValue={subject.phone!}
      />

      <Form.Input
        name="cpf"
        label={t(`document_types.${DocumentTypes.CPF}`)}
        placeholder="000.000.000-00"
        mask="000.000.000-00"
        errors={state?.errors?.["cpf"]}
        defaultValue={subject.cpf!}
      />

      <Form.Input
        name="rg"
        label="RG"
        placeholder="RG"
        errors={state?.errors?.["rg"]}
        defaultValue={subject.rg!}
      />

      <Form.Select
        name="skin_color"
        label={t("labels.skin_color")}
        placeholder={t("labels.skin_color")}
        data={skinColors.map((skinColor) => ({
          label: skinColor.name,
          value: skinColor.id.toString(),
        }))}
        errors={state?.errors?.["skink_color"]}
        defaultValue={subject.skin_color!}
      />

      <Heading
        h2
        className="mt-5"
      >
        {t("labels.additional_information")}
      </Heading>

      <Form.Input
        name="father_name"
        label={t("labels.father_name")}
        placeholder={t("labels.father_name")}
        errors={state?.errors?.["father_name"]}
        defaultValue={subject.father_name!}
      />

      <Form.Input
        name="mother_name"
        label={t("labels.mother_name")}
        placeholder={t("labels.mother_name")}
        errors={state?.errors?.["mother_name"]}
        defaultValue={subject.mother_name!}
      />

      <Form.Input
        name="relative_name"
        label={t("labels.relative_relation")}
        placeholder={t("labels.name")}
        errors={state?.errors?.["relative_name"]}
        defaultValue={subject.relative_name!}
      />

      <Form.Input
        name="relative_relation_type"
        label={t("labels.relative_relation_type")}
        placeholder={t("labels.relative_relation_type")}
        errors={state?.errors?.["relative_relation_type"]}
        defaultValue={subject.relative_relation_type!}
      />

      <Form.Input
        name="relative_phone"
        label={t("labels.phone")}
        placeholder="(00) 0000-0000"
        mask={[{ mask: "(00) 0000-0000" }, { mask: "(00) 00000-0000" }]}
        errors={state?.errors?.["relative_phone"]}
        defaultValue={subject.relative_phone!}
      />

      <Heading
        h2
        className="mt-5"
      >
        {t("labels.address")}
      </Heading>

      <Form.SelectQueryParam
        name="state_id"
        label={t("labels.state")}
        placeholder={t("labels.state")}
        data={states.map((state) => ({ label: state.name, value: state.id.toString() }))}
        queryName="state"
        errors={state?.errors?.["addresses.0.state_id"]}
        defaultValue={address?.state_id}
      />

      <Form.Select
        name="city_id"
        label={t("labels.city")}
        placeholder={t("labels.city")}
        data={cities.map((city) => ({ label: city.name, value: city.id.toString() }))}
        disabled={cities.length == 0}
        errors={state?.errors?.["addresses.0.city_id"]}
        defaultValue={address?.city_id}
      />

      <Form.Input
        name="street"
        label={t("labels.street")}
        placeholder={t("labels.street")}
        errors={state?.errors?.["addresses.0.street"]}
        defaultValue={address?.street}
      />

      <Form.Input
        name="number"
        label={t("labels.number")}
        placeholder={t("labels.number")}
        errors={state?.errors?.["addresses.0.number"]}
        defaultValue={address?.number}
      />

      <Form.Input
        name="district"
        label={t("labels.district")}
        placeholder={t("labels.district")}
        errors={state?.errors?.["addresses.0.district"]}
        defaultValue={address?.district}
      />

      <Form.Input
        name="complement"
        label={t("labels.complement")}
        placeholder={t("labels.complement")}
        errors={state?.errors?.["addresses.0.complement"]}
        defaultValue={address?.complement!}
      />

      <Form.Button
        className="self-end"
        data-testid={testIDs.SUBMIT_BUTTON}
      >
        {t(`general_actions.update`)}
      </Form.Button>
    </Form>
  );
}
