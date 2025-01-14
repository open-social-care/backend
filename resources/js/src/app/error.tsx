"use client"; // Error components must be Client Components

import { HBox } from "@/components/containers";
import { Button, Heading } from "@/components/ui";
import { t } from "@/lang";
import Image from "next/image";
import { useEffect } from "react";
import { AiOutlineReload } from "react-icons/ai";

export default function Error({
  error,
  reset,
}: {
  error: Error & { digest?: string };
  reset: () => void;
}) {
  useEffect(() => {
    if (process.env.NODE_ENV !== "production") {
      console.error(error);
    }
  }, [error]);

  return (
    <div className="flex h-screen flex-col bg-gray-100 ">
      <HBox className="m-5 justify-end">
        <Button href="/profile-select">{t("labels.start")}</Button>
      </HBox>

      <div className="m-10 mt-7 flex flex-col-reverse items-center grayscale lg:flex-row ">
        <div className="lg:w-[50%]">
          <Heading h1>{t("errors.generic_next_error")}</Heading>

          <button
            className="mt-4 flex transform items-center rounded-md px-3 py-2 text-gray-600 transition-colors duration-300 hover:bg-gray-200 hover:text-gray-700"
            onClick={
              // Attempt to recover by trying to re-render the segment
              () => reset()
            }
          >
            <span className="h-5 w-5">
              <AiOutlineReload />
            </span>

            <p className="text-md mx-2 font-normal">{t("errors.generic_next_error_try_again")}</p>
          </button>
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
    </div>
  );
}
