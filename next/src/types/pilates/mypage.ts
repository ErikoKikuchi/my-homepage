export type MypageData = {
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
