const PlaceholderPage = ({ title, subtitle }: { title: string; subtitle: string }) => (
  <div>
    <div className="erp-page-header">
      <h1 className="erp-page-title">{title}</h1>
      <p className="erp-page-subtitle">{subtitle}</p>
    </div>
    <div className="erp-card flex items-center justify-center h-64 text-muted-foreground">
      Módulo en desarrollo
    </div>
  </div>
);

export default PlaceholderPage;
