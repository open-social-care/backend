import { HBox } from "@/components/containers";
import { FullscreenLayout } from "@/components/layouts";
import { Button, Heading, Text } from "@/components/ui";
import { t } from "@/lang";
import Image from "next/image";

export default function page() {
  return (
    <FullscreenLayout.Root className="w-full">
      <HBox className="m-5 justify-end">
        <Button href="/login">{t("landing_page.login")}</Button>
      </HBox>

      <div className="m-10 flex flex-col-reverse items-center lg:flex-row">
        <div className="lg:w-[50%]">
          <Heading h1>{t("landing_page.title")}</Heading>

          <Text className="mt-4 font-medium">{t("landing_page.subtitle")}</Text>

          <Text className="mt-2 text-sm">{t("landing_page.content")}</Text>
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
    </FullscreenLayout.Root>
  );
}
