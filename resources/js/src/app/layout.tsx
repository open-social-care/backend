import type { Metadata } from "next";
import { PropsWithChildren } from "react";
import "./globals.css";

import dayjs from "dayjs";
import "dayjs/locale/pt-br";

dayjs.locale("pt-br");

export const metadata: Metadata = {
  title: "Social Care",
};

export default function RootLayout({ children }: PropsWithChildren) {
  return (
    <html lang="en">
      <body>{children}</body>
    </html>
  );
}
