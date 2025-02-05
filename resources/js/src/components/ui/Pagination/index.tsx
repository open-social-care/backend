"use client";

import { HBox } from "@/components/containers";
import useQueryParam from "@/hooks/useQueryParam";
import { Paginate } from "@/schemas";
import { LuChevronLeft, LuChevronRight, LuChevronsLeft, LuChevronsRight } from "react-icons/lu";
import { twMerge } from "tailwind-merge";
import Text from "../Text";
import PaginationButton from "./PaginationButton";

type PaginationProps = {
  paginate?: Paginate;
  queryName?: string;
} & React.ComponentPropsWithoutRef<"div">;

export default function Pagination({
  paginate,
  queryName,
  children,
  className,
  ...rest
}: PaginationProps) {
  const { value, set } = useQueryParam({ queryParam: queryName || "page" });

  if (!paginate || paginate.last_page == 1) {
    return;
  }

  return (
    <HBox
      className={twMerge("mt-2 items-center justify-end", className)}
      {...rest}
    >
      <PaginationButton
        data-testid="first-page-button"
        disabled={paginate.current_page == 1}
        onClick={() => set("1")}
      >
        <LuChevronsLeft />
      </PaginationButton>

      <PaginationButton
        data-testid="prev-page-button"
        disabled={paginate.current_page == 1}
        onClick={() => set(`${paginate.current_page - 1}`)}
      >
        <LuChevronLeft />
      </PaginationButton>

      <Text className="w-12 text-center text-sm text-gray-600">
        {paginate.current_page}/{paginate.last_page}
      </Text>

      <PaginationButton
        data-testid="next-page-button"
        disabled={paginate.current_page == paginate.last_page}
        onClick={() => set(`${paginate.current_page + 1}`)}
      >
        <LuChevronRight />
      </PaginationButton>

      <PaginationButton
        data-testid="last-page-button"
        disabled={paginate.current_page == paginate.last_page}
        onClick={() => set(`${paginate.last_page}`)}
      >
        <LuChevronsRight />
      </PaginationButton>
    </HBox>
  );
}
