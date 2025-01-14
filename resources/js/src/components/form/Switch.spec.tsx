import { render, screen } from "@testing-library/react";
import Switch from "./Switch";

describe("Switch", () => {
  //   test("toggles the checkbox when clicked on the switch element", async () => {
  //     render(
  //       <Switch
  //         label="My Label"
  //         name="mySwitch"
  //       />,
  //     );
  //     const input = screen.getByRole("checkbox");
  //     const switchElement = screen.getByTestId("checkbox");

  //     expect(switchElement).toHaveClass("bg-gray-200");
  //     await userEvent.click(input);
  //     expect(switchElement).toHaveClass("bg-teal-500");
  //   });

  test("renders errors when provided", async () => {
    render(
      <Switch
        label="My Label"
        name="mySwitch"
        errors={["Error 1", "Error 2"]}
      />,
    );

    const errorElements = screen.getByTestId("errors").childNodes;
    expect(errorElements).toHaveLength(2);
    expect(errorElements[0]).toHaveTextContent("Error 1");
    expect(errorElements[1]).toHaveTextContent("Error 2");
  });

  test("does not render errors when not provided", async () => {
    render(
      <Switch
        label="My Label"
        name="mySwitch"
      />,
    );
    const errorElements = screen.queryByTestId("errors")?.childNodes;
    expect(errorElements).toHaveLength(0);
  });
});
