import { fireEvent, render, screen } from "@testing-library/react";

import useQueryParam from "@/hooks/useQueryParam";
import SelectQueryParam from "./SelectQueryParam";

jest.mock("@/hooks/useQueryParam", () => ({
  __esModule: true,
  default: jest.fn().mockReturnValue({
    value: "",
    set: jest.fn(),
  }),
}));

const options = [
  { value: "1", label: "Option 1" },
  { value: "2", label: "Option 2" },
];

describe("SelectQueryParam", () => {
  it("renders the Select component with the correct props", () => {
    render(
      <SelectQueryParam
        queryName="myQuery"
        data={options}
      />,
    );

    const selectElement = screen.getByRole("combobox");
    expect(selectElement).toBeInTheDocument();
    expect(selectElement).toHaveValue("");
  });

  it("updates the query param when the selection changes", () => {
    const setMock = jest.fn();
    (useQueryParam as jest.Mock).mockReturnValue({ value: "", set: setMock });

    render(
      <SelectQueryParam
        queryName="myQuery"
        data={options}
      />,
    );

    const selectElement = screen.getByRole("combobox");
    fireEvent.change(selectElement, { target: { value: "2" } });

    expect(setMock).toHaveBeenCalledWith("2");
  });

  it("sets the default value from query param if no defaultValue is provided", () => {
    (useQueryParam as jest.Mock).mockReturnValue({ value: "1", set: jest.fn() });

    render(
      <SelectQueryParam
        queryName="myQuery"
        data={options}
      />,
    );

    const selectElement = screen.getByRole("combobox");
    expect(selectElement).toHaveValue("1");
  });
});
