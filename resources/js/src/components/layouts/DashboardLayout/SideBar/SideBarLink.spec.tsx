import "@testing-library/jest-dom";
import { render, screen } from "@testing-library/react";

import DashboardLayoutContextProvider from "../_context";
import SideBarLink from "./SideBarLink";

test("input should be rendered with label", async () => {
  const label = "Link name";

  render(
    <SideBarLink
      icon={<></>}
      href=""
    >
      {label}
    </SideBarLink>,
    {
      wrapper: DashboardLayoutContextProvider,
    },
  );

  expect(screen.getByTestId("label")).toHaveTextContent(label);
});
