"use client";

import useQueryParam from "@/hooks/useQueryParam";
import Select, { SelectProps } from "./Select";

type SelectQueryParamProps = SelectProps & {
  queryName: string;
};

export default function SelectQueryParam({ queryName, ...rest }: SelectQueryParamProps) {
  const { value, set } = useQueryParam({ queryParam: queryName });

  return (
    <Select
      {...rest}
      value={value}
      onChange={(e) => {
        set(e.currentTarget.value);
      }}
      defaultValue={rest.defaultValue?.toString() || value}
    />
  );
}
