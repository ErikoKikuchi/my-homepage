type NoticeCheckListProps = {
  notices: typeof reservationNotices;
  agreements: Agreements;
  onChange: (id: NoticeId, checked: boolean) => void;
  error?: string;
};
