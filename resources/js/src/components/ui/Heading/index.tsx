import { twMerge } from "tailwind-merge";

const variants = {
  h1: "text-2xl",
  h2: "text-xl",
  h3: "text-1xl",
};

type HeadingProps = React.ComponentPropsWithoutRef<"h1"> &
  (
    | { h1: boolean; h2?: never; h3?: never }
    | { h1?: never; h2: boolean; h3?: never }
    | { h1?: never; h2?: never; h3: boolean }
  );

export default function Heading({ h1, h2, h3, children, className, ...rest }: HeadingProps) {
  const variant = variants[(h3 && "h3") || (h2 && "h2") || "h1"];

  return (
    <h1
      className={twMerge("font-extrabold text-teal-900", variant, className)}
      {...rest}
    >
      {children}
    </h1>
  );
}
