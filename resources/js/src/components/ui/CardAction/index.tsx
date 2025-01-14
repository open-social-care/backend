import React, { ReactNode } from "react";
import { twMerge } from "tailwind-merge";
import Text from "../Text";

type CardActionProps = React.ComponentPropsWithoutRef<"a"> &
  (
    | {
        onClick: () => void;
        href?: never;
        title: string;
        icon?: ReactNode;
      }
    | {
        onClick?: never;
        href: string;
        title: string;
        icon?: ReactNode;
      }
  );

export default function CardAction({
  onClick,
  href,
  title,
  icon,
  className,
  ...rest
}: CardActionProps) {
  return (
    <a
      href={href}
      onClick={onClick}
      className={twMerge(
        "flex cursor-pointer flex-row items-center gap-2 disabled:cursor-not-allowed",
        className,
      )}
      {...rest}
    >
      {icon}
      <Text>{title}</Text>
    </a>
  );
}
