import { fetchCitiesByState, fetchStates } from "@/app/(auth)/_actions";
import { Paper } from "@/components/containers";
import { Heading } from "@/components/ui";
import subjectRefByOrganizationId from "@/helpers/subjectRefByOrganizationId";
import { t } from "@/lang";
import { State } from "@/schemas";
import { City } from "@/schemas/City";
import { SkinColor } from "@/schemas/SkinColor";
import { fetchFormInfo } from "../_actions";
import { CreateSubjectForm } from "./_form";

interface PageProps {
  params: {
    organizationId: number;
  };
  searchParams: {
    state: number;
  };
}

export default async function page({ params, searchParams }: PageProps) {
  const subjectRef = await subjectRefByOrganizationId(params.organizationId);

  const { data: formInfo } = await fetchFormInfo();
  const { data: statesData } = await fetchStates();
  const { data: citiesData } = await fetchCitiesByState(searchParams.state);

  const skinColors = SkinColor.array().parse(formInfo.skinColors);
  const states = State.array().parse(statesData);
  const { data: cities } = City.array().safeParse(citiesData);

  return (
    <>
      <Heading h1>{t("page_titles.create_subject", { subject_ref: subjectRef })}</Heading>

      <Paper className="mt-4">
        <CreateSubjectForm
          states={states}
          cities={cities || []}
          skinColors={skinColors}
        />
      </Paper>
    </>
  );
}
