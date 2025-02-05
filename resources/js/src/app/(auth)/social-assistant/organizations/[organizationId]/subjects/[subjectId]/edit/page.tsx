import { fetchCitiesByState, fetchStates } from "@/app/(auth)/_actions";
import { fetchSubjectAction } from "@/app/(auth)/social-assistant/_actions";
import { Paper } from "@/components/containers";
import { Heading } from "@/components/ui";
import { t } from "@/lang";
import { State, SubjectDetails } from "@/schemas";
import { City } from "@/schemas/City";
import { SkinColor } from "@/schemas/SkinColor";
import { fetchFormInfo } from "../../_actions";
import { UpdateSubjectForm } from "./_form";

interface PageProps {
  params: {
    subjectId: number;
  };
  searchParams: {
    state?: number;
  };
}

export default async function page({ params, searchParams }: PageProps) {
  const { data: subjectData } = await fetchSubjectAction(params.subjectId.toString());

  const subject = SubjectDetails.parse(subjectData);

  const { data: formInfo } = await fetchFormInfo();
  const { data: statesData } = await fetchStates();
  const { data: citiesData } = await fetchCitiesByState(
    searchParams.state || subject.addresses.findLast((a) => a)?.state_id,
  );

  const skinColors = SkinColor.array().parse(formInfo.skinColors);
  const states = State.array().parse(statesData);
  const { data: cities } = City.array().safeParse(citiesData);

  return (
    <>
      <Heading h1>{t("page_titles.edit_subject", { subject_ref: subject.name })}</Heading>

      <Paper className="mt-4">
        <UpdateSubjectForm
          subject={subject}
          states={states}
          cities={cities || []}
          skinColors={skinColors}
        />
      </Paper>
    </>
  );
}
