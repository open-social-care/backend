import { testIDs } from "@/e2e/_testIDs";
import { t } from "@/lang";
import { expect, test } from "@playwright/test";

test("create social assistant", async ({ page }) => {
  await page.goto("http://127.0.0.1:3000/manager/organizations/id/social-assistants/create");

  await page.getByTestId(testIDs.SUBMIT_BUTTON).click();

  await expect(page.getByText(/Verifique os erros nos campos!/)).toBeVisible();

  await page.getByPlaceholder(t("labels.name")).fill("Assistente");

  await page.getByPlaceholder("user@email.com").fill("social@example.com");

  await page.locator('input[name="password"]').fill("12345678");

  await page.locator('input[name="password_confirmation"]').fill("12345678");

  await page.getByTestId(testIDs.SUBMIT_BUTTON).click();

  await expect(page.getByText(/Registro criado com sucesso./)).toBeVisible();
});
