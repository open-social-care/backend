import { testIDs } from "@/e2e/_testIDs";
import { t } from "@/lang";
import { expect, test } from "@playwright/test";

test("create template", async ({ page }) => {
  await page.goto("http://127.0.0.1:3000/manager/organizations/id/form-templates/create");

  await page.getByTestId(testIDs.SUBMIT_BUTTON).click();

  await expect(page.getByText(/Verifique os erros nos campos!/)).toBeVisible();

  await page.getByPlaceholder(t("labels.name")).fill("Atendimento médico");

  await page.getByPlaceholder(t("labels.description")).fill("descrição");

  await page.getByTestId(testIDs.SUBMIT_BUTTON).click();

  await expect(page).toHaveURL("/manager/organizations/id/form-templates");
});
