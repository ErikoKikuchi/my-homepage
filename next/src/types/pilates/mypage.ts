export type AccountSettingsData = {
  profile: { name: string; name_kana: string | null; phone: string | null; occupation: string | null } | null;
  email: string;
  twoFactor: "enabled" | "pending" | "disabled";
};

export type Hope = { id: string; hope: string };

export type HopeHistoryEntry = Hope & {
  created_at: string;
  is_active: boolean;
  achieved_at: string | null;
};

export type MypageData = {
  clientName: string | null;
  hopes: Hope[];
  notLineLinkedClient: boolean;
  nextReservationInfo: { date: string; location: string | null } | null;
  remainingTicketCounts: number;
  upcomingReservations: {
    uuid: string;
    date: string;
    location: string | null;
  }[];
};
export type BookingDetailResponse = {
  booking: {
    participants: number;
    date: string;
    location: string;
    note: string | null;
    start_time: string;
    end_time: string;
  };
  isPastCutoff: boolean;
};
