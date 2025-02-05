import "@testing-library/jest-dom";
import { render, screen } from "@testing-library/react";
import userEvent from "@testing-library/user-event";

import { testIDs } from "@/e2e/_testIDs";
import Select, { SelectItem } from "./Select";

const options: SelectItem[] = [
  {
    label: "Option 1",
    value: "1",
  },
  {
    label: "Option 2",
    value: "2",
  },
];

// incremented 1 because select contains 1 option item by default
const optionsCount = options.length + 1;

const option2 = options[1];

jest.mock("next/navigation", () => ({
  ...jest.requireActual("next/navigation"),
  useRouter: () => ({ replace: jest.fn() }),
}));

test("options should be rendered", async () => {
  render(<Select data={options} />);

  const renderedOptions = screen.getAllByRole("option");

  expect(renderedOptions).toHaveLength(optionsCount);

  const renderedOption2 = renderedOptions.at(2);

  expect(renderedOption2).toHaveTextContent(option2.label);
  expect(renderedOption2).toHaveAttribute("value", option2.value);
});

test("option should be selected", async () => {
  render(<Select data={options} />);

  const select = screen.getByTestId(testIDs.SELECT);

  await userEvent.selectOptions(select, option2.label);

  expect(select).toHaveValue(option2.value);
});
