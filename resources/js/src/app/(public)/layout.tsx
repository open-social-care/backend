import { FullscreenLayout } from "@/components/layouts";
import { PropsWithChildren } from "react";

export default function layout({ children }: PropsWithChildren) {
  return <FullscreenLayout.Root centered>{children}</FullscreenLayout.Root>;
}
