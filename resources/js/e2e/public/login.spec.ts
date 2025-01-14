import { t } from "@/lang";
import { expect, test } from "@playwright/test";
import { testIDs } from "../_testIDs";

test("test page title", async ({ page }) => {
  await page.goto("./login");

  await expect(page).toHaveTitle(t("auth.login"));
});

test("test successful login", async ({ page }) => {
  await page.goto("./login");

  await page.getByPlaceholder("your@email.com").fill("admin@socialcare.com");

  await page.getByPlaceholder("********").fill("12345678");

  await page.getByTestId(testIDs.SUBMIT_BUTTON).click();

  await expect(page).toHaveURL("/profile-select");
});

test("test unsuccessful login", async ({ page }) => {
  await page.goto("./login");

  await page.getByPlaceholder("your@email.com").fill("error@email.com");

  await page.getByPlaceholder("********").fill("error");

  await page
    .getByRole("button", {
      name: t("auth.login"),
    })
    .click();

  await expect(page).toHaveURL("/login");

  await expect(page.getByText(/Credenciais de login inválidas/)).toBeVisible();
});
