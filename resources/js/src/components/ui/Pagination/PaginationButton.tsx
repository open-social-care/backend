import { twMerge } from "tailwind-merge";

type PaginationButtonProps = {
  disabled?: boolean;
} & React.ComponentPropsWithoutRef<"a">;

const pageLinkDefaultClasses =
  "mx-1 flex transform shadow-md items-center justify-center rounded-lg border bg-white px-2 py-2 text-gray-600 transition-colors duration-300 hover:bg-gray-100 hover:text-gray-700 cursor-pointer";

const pageLinkDisabledClasses = "pointer-events-none bg-gray-200 text-gray-400";

export default function PaginationButton({
  onClick,
  children,
  disabled = false,
  ...rest
}: PaginationButtonProps) {
  return (
    <a
      className={twMerge(pageLinkDefaultClasses, disabled && pageLinkDisabledClasses, "ml-0 w-12")}
      onClick={onClick}
      {...rest}
    >
      {children}
    </a>
  );
}
