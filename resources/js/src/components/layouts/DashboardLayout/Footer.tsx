import dayjs from "dayjs";
import { twMerge } from "tailwind-merge";

export default function Footer({
  children,
  className,
  ...rest
}: React.ComponentPropsWithoutRef<"div">) {
  return (
    <div
      className={twMerge(
        "fixed bottom-0 flex h-4 w-full items-center justify-end border-t border-gray-200 bg-white px-2",
        className,
      )}
      {...rest}
    >
      <p className="text-xs text-gray-500">Open Social Care® {dayjs().year()}</p>
    </div>
  );
}
