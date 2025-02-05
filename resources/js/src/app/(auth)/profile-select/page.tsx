import auth from "@/auth";
import { VBox } from "@/components/containers";
import { Heading, Text } from "@/components/ui";
import { Roles } from "@/enums/Roles";
import { t } from "@/lang";
import Image from "next/image";
import { FaUserCheck, FaUserCog, FaUserSecret } from "react-icons/fa";
import ProfileLink from "./_profile-link";

export default async function page() {
  const { user } = await auth();

  return (
    <div className="m-10 flex flex-col-reverse items-center lg:flex-row">
      <div className="lg:w-[50%]">
        <Heading h1>{t("page_titles.profile_select", { name: user.name })}</Heading>

        {user.roles_ids?.length == 0 && <Text>{t("informations.role_not_found")}</Text>}

        {user.roles_ids && (
          <VBox className="mt-4">
            {user.roles_ids.includes(1) && (
              <ProfileLink
                icon={<FaUserSecret />}
                href={`/${Roles.ADMIN}/organizations`}
              >
                {t(`roles.${Roles.ADMIN}`)}
              </ProfileLink>
            )}

            {user.roles_ids.includes(2) && (
              <ProfileLink
                icon={<FaUserCog />}
                href={`/${Roles.MANAGER}/organizations`}
              >
                {t(`roles.${Roles.MANAGER}`)}
              </ProfileLink>
            )}

            {user.roles_ids.includes(3) && (
              <ProfileLink
                icon={<FaUserCheck />}
                href={`/${Roles.SOCIAL_ASSISTANT}/organizations`}
              >
                {t(`roles.${Roles.SOCIAL_ASSISTANT}`)}
              </ProfileLink>
            )}
          </VBox>
        )}
      </div>

      <Image
        className="w-full sm:mb-16 sm:w-[50%]"
        src="/images/landing-page-image.svg"
        alt="landing-page"
        width="4000"
        height="2667"
        priority
      />
    </div>
  );
}
