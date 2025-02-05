import { usePathname, useRouter, useSearchParams } from "next/navigation";

type useQueryParamProps = {
  queryParam: string;
  paramsToDeleteOnSet?: string[];
};

export default function useQueryParam({ queryParam, paramsToDeleteOnSet }: useQueryParamProps) {
  const searchParams = useSearchParams();
  const pathname = usePathname();
  const { replace } = useRouter();

  const params = new URLSearchParams(searchParams);

  const set = (value?: string | null) => {
    if (value) {
      params.set(queryParam, value);
      paramsToDeleteOnSet?.map((p) => params.delete(p));
    } else {
      params.delete(queryParam);
    }

    replace(`${pathname}?${params.toString()}`, { scroll: false });
  };

  const del = () => {
    params.delete(queryParam);

    replace(`${pathname}?${params.toString()}`);
  };

  return { value: searchParams.get(queryParam)?.toString(), set, del };
}
