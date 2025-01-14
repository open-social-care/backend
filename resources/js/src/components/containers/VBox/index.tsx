import { twMerge } from "tailwind-merge";
import HBox from "../HBox";

export default function VBox({
  children,
  className,
  ...rest
}: React.ComponentPropsWithoutRef<"div">) {
  return (
    <HBox
      className={twMerge("flex-col", className)}
      {...rest}
    >
      {children}
    </HBox>
  );
}
