"use client";

import { VBox } from "@/components/containers";
import FlashMessage from "@/components/form/FlashMessage";
import { useEffect, useState } from "react";
import { ActionFlashProps, getActionFlashes } from ".";

const ACTION_FLASH_TIMEOUT_MS = 5000;

export const ActionFlashes = () => {
  const [flash, setFlash] = useState<ActionFlashProps[]>([]);

  useEffect(() => {
    getActionFlashes().then((sf) => {
      if (sf.length > 0) {
        setFlash(sf);

        setTimeout(() => {
          setFlash([]);
        }, ACTION_FLASH_TIMEOUT_MS);
      }
    });
  }, []);

  if (flash.length == 0) return;

  return (
    <VBox className="absolute bottom-6 right-6">
      {flash.map((f, index) => (
        <FlashMessage
          key={index}
          type={f.type}
          message={f.message}
        />
      ))}
    </VBox>
  );
};
