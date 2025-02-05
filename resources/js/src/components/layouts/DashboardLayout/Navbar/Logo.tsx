import Image from "next/image";
import Link from "next/link";

type LogoProps = {
  href: string;
};

export default function Logo({ href }: LogoProps) {
  return (
    <Link href={href}>
      <Image
        className="w-36 sm:w-[245px]"
        draggable={false}
        src="/images/logo.png"
        alt="logo"
        width="282"
        height="65" //keep aspect ratio from the original image
        priority
      />
    </Link>
  );
}
