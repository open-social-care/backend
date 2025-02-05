import { twMerge } from "tailwind-merge";

type FlashMessageProps = {
  type: "error" | "success";
  message: string;
} & React.ComponentPropsWithoutRef<"div">;

const variants = {
  successClasses: "bg-emerald-500",
  errorClasses: "bg-red-500",
};

export default function FlashMessage({ type, message, className, ...rest }: FlashMessageProps) {
  if (!type || !message) return <div />;

  return (
    <div
      {...rest}
      className={twMerge(
        "w-full rounded-lg border p-4 text-sm text-white",
        className,
        variants[`${type}Classes`],
      )}
    >
      {message}
    </div>
  );
}
